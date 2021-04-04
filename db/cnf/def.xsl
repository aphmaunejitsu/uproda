<?xml version="1.0" encoding="utf8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:s="http://sqlfairy.sourceforge.net/sqlfairy.xml">
<xsl:output method="text" encoding="utf8"/>
<xsl:template match="table_structure">
# <xsl:value-of select="@name"/> - <xsl:value-of select="options/@Comment"/>
## column
name|type|NULL|default|key|comment|Extra
----|----|----|----|----|---|---|
<xsl:apply-templates select="field"/>
## index
name|column|multi|NULL|UNIQ
----|----|----|----|----
<xsl:apply-templates select="key"/>
</xsl:template>

<xsl:template match="field">
<xsl:value-of select="@Field"/>|<xsl:value-of select="@Type"/>|<xsl:value-of select="@Null"/>|<xsl:choose><xsl:when test="@Default != ''"><xsl:value-of select="@Default"/></xsl:when><xsl:otherwise></xsl:otherwise></xsl:choose>|<xsl:choose><xsl:when test="@Key != ''"><xsl:value-of select="@Key"/></xsl:when><xsl:otherwise></xsl:otherwise></xsl:choose>|<xsl:choose><xsl:when test="@Comment != ''"><xsl:value-of select="@Comment"/></xsl:when><xsl:otherwise></xsl:otherwise></xsl:choose>|<xsl:choose><xsl:when test="@Extra != ''"><xsl:value-of select="@Extra"/></xsl:when><xsl:otherwise></xsl:otherwise></xsl:choose>|<xsl:text>
</xsl:text>
</xsl:template>

<xsl:template match="key">
<xsl:value-of select="@Key_name"/>|<xsl:value-of select="@Column_name"/>|<xsl:value-of select="@Seq_in_index"/>|<xsl:choose><xsl:when test="@Null != ''"><xsl:value-of select="@Null"/></xsl:when><xsl:otherwise>NO</xsl:otherwise></xsl:choose>|<xsl:choose><xsl:when test="@Non_unique = '0'">YES</xsl:when><xsl:otherwise>NO</xsl:otherwise></xsl:choose>|<xsl:text>
</xsl:text>
</xsl:template>

</xsl:stylesheet>
